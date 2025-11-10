# Kubernetes Deployment Guide for Guapa Laravel Application

## Overview

This document explains how to deploy the Guapa Laravel application to Oracle Kubernetes Engine (OKE) with automatic CI/CD through GitHub Actions.

## Key Differences: Old VM vs Kubernetes

### Old VM Setup (Docker Compose)
- **Port Mapping**: `5050` → `80` (guapa-nginx-prod container)
- **Access**: `http://stage.guapa.com.sa:5050` or via reverse proxy
- **Database**: MySQL running in separate container
- **Deployment**: Manual via GitLab CI/CD SSH

### Kubernetes Setup
- **Port Mapping**: LoadBalancer external IP:80 → Service:80 → Pod:80
- **Access**: `http://145.241.154.57` (OCI LoadBalancer IP)
- **Database**: Managed externally or in cluster (MySQL StatefulSet recommended)
- **Deployment**: Automated via GitHub Actions

## Architecture

```
GitHub Push
    ↓
GitHub Actions Workflow
    ├── Build PHP-FPM Image → Push to OCIR
    ├── Build Nginx Image → Push to OCIR
    └── Trigger Kubernetes Deployment Restart
           ↓
        Kubernetes pulls new images
           ↓
        Rolling update (zero downtime)
           ↓
        LoadBalancer distributes traffic
           ↓
        Application available at http://145.241.154.57
```

## Port Configuration

### Kubernetes Ports
- **External**: LoadBalancer exposes port `80`
- **Pod Port**: `80` (Nginx container)
- **Internal**: `9000` (PHP-FPM container - internal communication only)
- **No port mapping needed** - Kubernetes handles all networking

### Customizing Ports

If you need a different external port, modify the LoadBalancer Service in your Terraform deployment:

```hcl
# In laravel-deployment.yaml.tpl or kubernetes configuration
spec:
  type: LoadBalancer
  ports:
    - protocol: TCP
      port: 5050          # External port (if you want to match old VM)
      targetPort: 80      # Pod port (Nginx port)
```

## Required GitHub Actions Secrets

Set these in **Settings** → **Secrets and variables** → **Actions**:

```bash
OCIR_USERNAME=ax45nhirzfe7/el3ashe2@gmail.com
OCIR_PASSWORD=<your-oci-auth-token>
KUBE_CONFIG=<base64-encoded-kubeconfig>
```

### How to Generate KUBE_CONFIG

```powershell
# Windows PowerShell
$config = Get-Content $env:USERPROFILE\.kube\config -Raw
$base64 = [Convert]::ToBase64String([Text.Encoding]::UTF8.GetBytes($config))
$base64 | Set-Clipboard
```

```bash
# Linux/Mac
cat ~/.kube/config | base64 | pbcopy
```

## Deployment Flow

1. **Push to GitHub**
   ```bash
   git add .
   git commit -m "Update feature"
   git push origin main
   ```

2. **GitHub Actions Triggers**
   - Checks out code
   - Builds PHP-FPM Docker image
   - Builds Nginx Docker image
   - Pushes both to OCIR

3. **Kubernetes Deployment**
   - Restarts `laravel-app` deployment
   - Pulls new images
   - Runs rolling update
   - Zero-downtime deployment

4. **Monitoring**
   ```bash
   # Watch deployment rollout
   kubectl rollout status deployment/laravel-app
   
   # Check pods
   kubectl get pods -l app=laravel
   
   # View logs
   kubectl logs -l app=laravel -c nginx
   kubectl logs -l app=laravel -c php-fpm
   ```

## Database Configuration

### Current Setup
- MySQL runs in Kubernetes as StatefulSet
- Credentials stored in `mysql-credentials` Kubernetes Secret
- SQL dump auto-imported on pod creation

### Environment Variables in Pods
```yaml
DB_CONNECTION: mysql
DB_HOST: mysql          # DNS name of MySQL service
DB_PORT: 3306
DB_DATABASE: laravel    # From secret
DB_USERNAME: laravel_user  # From secret
DB_PASSWORD: ...        # From secret
```

## Horizontal Pod Autoscaler (HPA)

The application automatically scales based on CPU usage:

```bash
# View HPA status
kubectl get hpa laravel-hpa

# Manual scaling (if needed)
kubectl scale deployment laravel-app --replicas=5
```

**Configuration:**
- Min replicas: `2`
- Max replicas: `10`
- Scale trigger: `70%` CPU utilization

## Troubleshooting

### Pods not updating after push?
```bash
# Force restart deployment
kubectl rollout restart deployment/laravel-app

# Check rollout status
kubectl rollout status deployment/laravel-app
```

### Image pull errors?
```bash
# Check if OCIR secret exists
kubectl get secret ocirsecret

# Recreate if needed
kubectl create secret docker-registry ocirsecret \
  --docker-server=me-riyadh-1.ocir.io \
  --docker-username=ax45nhirzfe7/el3ashe2@gmail.com \
  --docker-password=<auth-token>
```

### Check container logs
```bash
# Nginx logs
kubectl logs -l app=laravel -c nginx --tail=100

# PHP-FPM logs
kubectl logs -l app=laravel -c php-fpm --tail=100

# Describe pod for events
kubectl describe pod <pod-name>
```

## From Old VM to Kubernetes Migration

### Verification Checklist
- [x] Database migrated/accessible from Kubernetes
- [x] Environment variables configured
- [x] Volume mounts removed (code in image, not volumes)
- [x] Port configuration updated
- [x] OCIR images built and pushed
- [x] GitHub Actions secrets configured
- [x] LoadBalancer IP assigned
- [x] Security lists allow port 80

### Testing Production Deployment
```bash
# 1. Check nodes are ready
kubectl get nodes

# 2. Check pods are running
kubectl get pods

# 3. Test application
curl http://145.241.154.57

# 4. Monitor logs during deployment
kubectl logs -f -l app=laravel -c nginx
```

## Kubernetes Commands Reference

```bash
# View resources
kubectl get nodes
kubectl get pods
kubectl get svc
kubectl get pvc
kubectl get hpa

# Detailed information
kubectl describe node <node-name>
kubectl describe pod <pod-name>
kubectl describe svc laravel-service

# Logs
kubectl logs <pod-name>
kubectl logs -f <pod-name>          # Follow logs
kubectl logs -l app=laravel         # All pods with label

# Scaling
kubectl scale deployment laravel-app --replicas=3

# Rollout
kubectl rollout restart deployment/laravel-app
kubectl rollout status deployment/laravel-app
kubectl rollout history deployment/laravel-app
kubectl rollout undo deployment/laravel-app  # Rollback

# Exec into pod
kubectl exec -it <pod-name> -- bash

# Apply configurations
kubectl apply -f deployment.yaml
```

## Support & Resources

- [OKE Documentation](https://docs.oracle.com/en-us/iaas/Content/ContEng/home.htm)
- [Kubernetes Documentation](https://kubernetes.io/docs/)
- [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)
