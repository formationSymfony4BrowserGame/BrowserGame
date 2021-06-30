import axios from 'axios'
/* import game from './game' */

const save = document.getElementById('save_game')

const Game = {
  playerCount: 3,
  hand: ['ver', 'ver', 3, 3],
  remainingDices: [21, 22, 23, 24],
  skewer: [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36],
  state: 'loading',
  players: ['pseudo1', 'pseudo2', 'pseudo4']
}

const handleSubmit = (x) => {
  axios({
    method: 'post',
    url: '/save',
    data: x,
    transformRequest: [
      function (data, headers) {
        const serializedData = []
        for (const k in data) {
          if (data[k]) {
            serializedData.push(`${k}=${encodeURIComponent(data[k])}`)
          }
        }
        return serializedData.join('&')
      }
    ]
  })
}
save.onclick = () => handleSubmit(Game)
