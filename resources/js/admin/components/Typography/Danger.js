import React from "react"
import PropTypes from "prop-types"
// @material-ui/core components
import { withStyles } from "@material-ui/core/styles"
// core components
import styles from "./typographyStyle.js"

const Danger = props => {
  const { classes, children } = props
  return (
    <span className={classes.defaultFontStyle + " " + classes.dangerText}>
      {children}
    </span>
  )
}

Danger.propTypes = {
  children: PropTypes.node
}

export default withStyles(styles)(Danger)
