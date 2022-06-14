import React from 'react'
import { Link } from 'react-router-dom'
import CommentIcon from '@material-ui/icons/Comment'
import { withStyles } from '@material-ui/core/styles'
import { Button } from 'react-admin'

const styles = {
  button: {
    marginTop: '1em',
    marginBottom: '1em',
  }
}

const AddCommentButton = ({ classes, record }) => (
  <Button
    className={classes.button}
    variant="contained"
    component={Link}
    to={`/comments/create?post_id=${record.id}`}
    label="Add new comment"
    title="Add new comment"
  >
    <CommentIcon />
  </Button>
)

export default withStyles(styles)(AddCommentButton)
