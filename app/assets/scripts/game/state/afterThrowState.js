import render from '../render'

const afterThrowState = (data) => {
  data.state = 'afterThrowState'
  render(data)
}

export default afterThrowState
