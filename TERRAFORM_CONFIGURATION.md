# Terraform Configuration Guide for Kubernetes Deployment

This document explains what changes need to be made to your Terraform project to complete the Kubernetes deployment setup for the Guapa Laravel application.

## Overview of Required Changes

Your Terraform project already has most of the infrastructure in place. The following changes are required to enable the new GitHub Actions CI/CD pipeline and optimize the deployment:

## 1. Update `variables.tf`

Remove the old `laravel_image` variable (if it exists) and ensure these are present:

```hcl
# REMOVE THIS (if it exists):
# variable "laravel_image" {
#   description = "Laravel Docker image"
#   type        = string
# }

# KEEP/ADD THESE:

variable "laravel_replicas_min" {
  description = "Minimum Laravel replicas"
  type        = number
  default     = 2
}

variable "laravel_replicas_max" {
  description = "Maximum Laravel replicas for autoscaling"
  type        = number
  default     = 10
}

variable "laravel_replicas_cpu_trigger" {
  description = "CPU utilization percentage to trigger scaling"
  type        = number
  default     = 70
}
```

## 2. Update `terraform.tfvars`

Replace with:

```hcl
# Remove laravel_image line

# Update/add these:
laravel_replicas_min            = 2
laravel_replicas_max            = 10
laravel_replicas_cpu_trigger    = 70
```

## 3. Verify `laravel-deployment.tf`

Your current file should look like this (no changes needed if already correct):

```hcl
resource "local_file" "laravel_deployment" {
  content = templatefile("${path.module}/templates/laravel-deployment.yaml.tpl", {
    replicas_min     = var.laravel_replicas_min
    replicas_max     = var.laravel_replicas_max
  })
  
  filename             = "./generated/laravel-deployment.yaml"
  file_permission      = "0644"
  directory_permission = "0755"
}

resource "null_resource" "deploy_laravel" {
  triggers = {
    manifest_content = local_file.laravel_deployment.content
  }

  provisioner "local-exec" {
    interpreter = ["PowerShell", "-Command"]
    command     = "kubectl apply -f ${local_file.laravel_deployment.filename}"
  }

  depends_on = [
    null_resource.deploy_mysql_with_init,
    local_file.laravel_deployment
  ]
}
```

## 4. Verify `templates/laravel-deployment.yaml.tpl`

Should match this structure (already created in GitHub):

```yaml
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: laravel-app
  namespace: default
spec:
  replicas: ${replicas_min}
  selector:
    matchLabels:
      app: laravel
  template:
    metadata:
      labels:
        app: laravel
    spec:
      imagePullSecrets:
      - name: ocirsecret
      containers:
      # Nginx container
      - name: nginx
        image: me-riyadh-1.ocir.io/ax45nhirzfe7/guapa:nginx-prod
        ports:
        - containerPort: 80
          name: http
        resources:
          requests:
            cpu: "100m"
            memory: "128Mi"
          limits:
            cpu: "200m"
            memory: "256Mi"
      
      # PHP-FPM Laravel container
      - name: php-fpm
        image: me-riyadh-1.ocir.io/ax45nhirzfe7/guapa:fpm-prod
        env:
        - name: DB_CONNECTION
          value: "mysql"
        - name: DB_HOST
          value: "mysql"
        - name: DB_PORT
          value: "3306"
        - name: DB_DATABASE
          valueFrom:
            secretKeyRef:
              name: mysql-credentials
              key: mysql-database
        - name: DB_USERNAME
          valueFrom:
            secretKeyRef:
              name: mysql-credentials
              key: mysql-user
        - name: DB_PASSWORD
          valueFrom:
            secretKeyRef:
              name: mysql-credentials
              key: mysql-password
        resources:
          requests:
            cpu: "200m"
            memory: "512Mi"
          limits:
            cpu: "500m"
            memory: "1Gi"

---
apiVersion: v1
kind: Service
metadata:
  name: laravel-service
  namespace: default
spec:
  type: LoadBalancer
  selector:
    app: laravel
  ports:
    - protocol: TCP
      port: 80
      targetPort: 80

---
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: laravel-hpa
  namespace: default
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: laravel-app
  minReplicas: ${replicas_min}
  maxReplicas: ${replicas_max}
  metrics:
  - type: Resource
    resource:
      name: cpu
      target:
        type: Utilization
        averageUtilization: 70
```

## 5. Optional: Add Port Customization

If you want to match the old VM setup (port 5050), modify the LoadBalancer Service in the template:

```yaml
# In templates/laravel-deployment.yaml.tpl, Service section:
spec:
  type: LoadBalancer
  selector:
    app: laravel
  ports:
    - protocol: TCP
      port: 5050          # External port (old VM port)
      targetPort: 80      # Pod port (Nginx port)
```

Then add to `variables.tf`:

```hcl
variable "laravel_external_port" {
  description = "External LoadBalancer port"
  type        = number
  default     = 80  # Change to 5050 if needed
}
```

And update template call in `laravel-deployment.tf`:

```hcl
content = templatefile("${path.module}/templates/laravel-deployment.yaml.tpl", {
  replicas_min      = var.laravel_replicas_min
  replicas_max      = var.laravel_replicas_max
  external_port     = var.laravel_external_port
})
```

Then in the template:

```yaml
spec:
  type: LoadBalancer
  selector:
    app: laravel
  ports:
    - protocol: TCP
      port: ${external_port}
      targetPort: 80
```

## 6. Verify Security List Configuration (network.tf)

Your `network.tf` should already have the HTTP ingress rule added. Verify it exists:

```hcl
# In oke_lb_security_list:
ingress_security_rules {
  protocol    = "6" # TCP
  source      = "0.0.0.0/0"
  source_type = "CIDR_BLOCK"
  stateless   = false

  tcp_options {
    min = 80
    max = 80
  }

  description = "Allow HTTP traffic from internet"
}

# Optional: Add HTTPS if needed
ingress_security_rules {
  protocol    = "6" # TCP
  source      = "0.0.0.0/0"
  source_type = "CIDR_BLOCK"
  stateless   = false

  tcp_options {
    min = 443
    max = 443
  }

  description = "Allow HTTPS traffic from internet"
}
```

## 7. Execution Steps

After making these changes:

### Step 1: Validate Terraform
```bash
terraform validate
```

### Step 2: Plan Changes
```bash
terraform plan
```

### Step 3: Apply Changes
```bash
terraform apply
```

### Step 4: Verify Deployment
```bash
kubectl get nodes
kubectl get pods
kubectl get svc laravel-service
```

### Step 5: Test Application
```bash
curl http://145.241.154.57
# or
curl http://145.241.154.57:5050  # if using port 5050
```

## Summary of File Changes

| File | Change | Action |
|------|--------|--------|
| `variables.tf` | Remove `laravel_image` variable | ✏️ EDIT |
| `terraform.tfvars` | Remove `laravel_image` value | ✏️ EDIT |
| `laravel-deployment.tf` | Verify structure matches guide | ✅ VERIFY |
| `templates/laravel-deployment.yaml.tpl` | Use hardcoded image names | ✅ VERIFY |
| `network.tf` | Add HTTP/HTTPS ingress rules | ✅ VERIFY |

## GitHub Actions Integration

Once these Terraform changes are applied:

1. Push changes to GitHub:
   ```bash
   git add .
   git commit -m "Update Terraform for Kubernetes CI/CD"
   git push origin main
   ```

2. GitHub Actions will automatically:
   - Build Docker images
   - Push to OCIR
   - Trigger Kubernetes deployment restart

3. Verify the workflow in GitHub → Actions tab

## Troubleshooting

### Terraform validation fails?
```bash
terraform fmt -recursive
terraform validate
```

### Pods not starting after terraform apply?
```bash
kubectl get pods -l app=laravel
kubectl describe pod <pod-name>
kubectl logs <pod-name>
```

### ImagePullBackOff error?
```bash
# Verify OCIR secret exists
kubectl get secret ocirsecret

# Check image names in deployment
kubectl get deployment laravel-app -o yaml | grep image
```

### LoadBalancer stuck in pending?
```bash
kubectl describe svc laravel-service
```

Check if security list allows port 80/443.

## Checklist

- [ ] Remove `laravel_image` variable from `variables.tf`
- [ ] Remove `laravel_image` from `terraform.tfvars`
- [ ] Verify `laravel-deployment.tf` uses template correctly
- [ ] Verify `templates/laravel-deployment.yaml.tpl` exists and is correct
- [ ] Verify HTTP ingress rule in `network.tf`
- [ ] Run `terraform validate`
- [ ] Run `terraform plan` and review changes
- [ ] Run `terraform apply`
- [ ] Verify nodes are Ready: `kubectl get nodes`
- [ ] Verify pods are Running: `kubectl get pods`
- [ ] Test application: `curl http://145.241.154.57`
- [ ] Set GitHub Secrets (OCIR_USERNAME, OCIR_PASSWORD, KUBE_CONFIG)
- [ ] Push code to GitHub to trigger CI/CD

## Next Steps

After completing these Terraform changes:

1. **GitHub Actions will handle everything automatically**
2. Every push to `main` triggers:
   - Image builds
   - OCIR push
   - Kubernetes deployment restart

3. **Your application will be live** at `http://145.241.154.57`
