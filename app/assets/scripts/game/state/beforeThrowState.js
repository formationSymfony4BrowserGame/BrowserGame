import render from '../render'

const beforeThrowState = (data) => {
  data.state = 'beforeThrowState'
  render(data)
}

export default beforeThrowState
