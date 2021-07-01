import axios from 'axios'

const save = document.getElementById('save_game')

const game = {
  playerCount: 3,
  currentPlayer: 1,
  hand: [4, 4, 3, 3],
  remainingDices: [21, 22, 23, 24],
  gameState: 'loading',
  player0: ['pseudo1', 21, 22],
  player1: ['pseudo2', 31, 33],
  player2: ['pseudo3', 26, 25]
}

const handleSubmit = (game) => {
  axios({
    method: 'post',
    url: '/save',
    data: game,
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
save.onclick = () => handleSubmit(game)
