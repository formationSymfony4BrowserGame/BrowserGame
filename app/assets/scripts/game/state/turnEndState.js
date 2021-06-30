import render from '../render'

const turnEndState = (isHandNull, data) => {
  data.state = 'turnEndState'
  render(data)
}

export default turnEndState
