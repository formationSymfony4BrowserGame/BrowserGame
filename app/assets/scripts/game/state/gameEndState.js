import render from '../render'

const gameEndState = (data) => {
  data.state = 'gameEndState'
  render(data)
}

export default gameEndState
