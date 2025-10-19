import React from "react"
// nodejs library to set properties for components
import PropTypes from "prop-types"
// @material-ui/core components
import { withStyles } from "@material-ui/core/styles"
import Grid from "@material-ui/core/Grid"

const styles = {
  grid: {
    padding: "0 15px !important",
  }
};

const GridItem = props => {
  const { classes, children, ...rest } = props
  return (
    <Grid item {...rest} className={classes.grid}>
      {children}
    </Grid>
  )
}

GridItem.propTypes = {
  children: PropTypes.node
}

export default withStyles(styles)(GridItem)