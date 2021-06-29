import render from '../render'

const turnEndState = (data) => {
  data.state = 'turnEndState'
  render(data)
}

export default turnEndState
