import render from '../render'
import axios from 'axios'

export const gameEndSave = async (data) => {
  const response = await axios({
    method: 'post',
    url: '/save',
    data: JSON.stringify(data)
  }).catch((err) => {
    console.error(err)
    return false
  })
  data.idGame = response.data
  return true
}

const gameEndState = (data) => {
  data.state = 'gameEndState'
  render(data)
}

export default gameEndState
