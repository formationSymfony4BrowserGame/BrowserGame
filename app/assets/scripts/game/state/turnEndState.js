import render from '../render'

const turnEndState = (isHandNull, data) => {
  data.state = 'turnEndState'
  render(data)
  console.log('turnEndState')
}

export default turnEndState
