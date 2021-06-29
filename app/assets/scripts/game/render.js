import htmlToElement from 'html-to-element'
import { throwDices } from './state/beginingState'
import { getChoosableValues, chooseValue } from './state/afterThrowState'

// main render function
const render = (data) => {
  switch (data.state) {
    case 'loading':
      updateSkewer(data)
      updateRemainingDices(data)
      updateHand(data)
      updatePlayersScore(data)
      break
    case 'beginingState':
      // Colorer le nom du joueur en cours
      colorCurrentPlayerName(data)
      // Activer le bouton "lancer"
      setDisableButton(false, data)
      break
    case 'afterThrowState':
      // cleanup from previous state
      setDisableButton(true, data)
      // add buttons for each choosable values
      setChoosableValuesButtons(false, data)
      break
    case 'beforeThrowState':
      // cleanup from previous state
      setChoosableValuesButtons(true, data)
  }
}
export default render

// html element definition
const pickominoElement = (pickomino) => htmlToElement(`
    <div class="pickomino column">
      <p class="value">${pickomino}</p>
      <p class="worms">${Math.floor((pickomino - 21) / 4) + 1} vers</p>
    </div>
`)
const emptyPickominoElement = () => htmlToElement('<div class="pickomino column"></div>')

const diceElement = (value) => htmlToElement(`
  <div class="column dice">
    <span class="value">${value === 6 ? 'ver' : value}</span>
  </div>
`)

const emptyDiceElement = () => htmlToElement('<div class="column dice empty"></div>')

const choosableValueButton = (value) => htmlToElement(`
  <button class="column button is-outlined is-primary mx-6">
    ${value === 6 ? 'ver' : value}
  </button>
`)

// fuctions used for render
const updateSkewer = (data) => {
  const skewer = document.getElementById('skewer')
  skewer.innerHTML = ''
  data.skewer.forEach(pickomino => {
    skewer.appendChild(pickominoElement(pickomino))
  })
}

const updatePlayersScore = (data) => {
  data.players.forEach(player => {
    const playerScore = document.getElementById(player.ranking + '-score')
    let score = 0
    player.pickominos.forEach(pickomino => {
      score += Math.floor((pickomino - 21) / 4) + 1
    })
    playerScore.children[0].children[0].innerHTML = score
    if (player.pickominos.length > 0) {
      playerScore.replaceChild(pickominoElement(player.pickominos[player.pickominos.length - 1]), playerScore.children[1])
    } else {
      playerScore.replaceChild(emptyPickominoElement(), playerScore.children[1])
    }
  })
}

export const updateRemainingDices = (data) => {
  const remainingDices = document.getElementById('remaining-dices')
  remainingDices.innerHTML = ''
  for (let i = 0; i < 8; i++) {
    const dice = data.remainingDices[i]
    if (dice !== undefined) {
      remainingDices.appendChild(diceElement(dice))
    } else {
      remainingDices.appendChild(emptyDiceElement())
    }
  }
}

export const updateHand = (data) => {
  const hand = document.getElementById('hand')
  hand.innerHTML = ''
  for (let i = 0; i < 8; i++) {
    const dice = data.hand[i]
    if (dice !== undefined) {
      hand.appendChild(diceElement(dice))
    } else {
      hand.appendChild(emptyDiceElement())
    }
  }
}

const colorCurrentPlayerName = (data) => {
  // récuperer l'objet du DOM
  const currentPlayerName = document.getElementById(data.currentPlayer + '-name')
  // ajouter la class de Bulma pour changer la couleur
  Array.from(currentPlayerName.children).forEach((child) => child.classList.add('has-text-info'))
}

const setDisableButton = (value, data) => {
  // récuperer l'objet du DOM
  const throwButton = document.getElementById('button')
  // activer ou désactiver le boutton
  if (value) {
    throwButton.setAttribute('disabled', String(value))
    throwButton.onclick = null
  } else {
    throwButton.removeAttribute('disabled')
    throwButton.onclick = () => throwDices(data)
  }
}

// set the content of the Choosable Values Buttons container
// if remove is true, it set the content as '' else it set it as a list of collection of buttons
const setChoosableValuesButtons = (remove, data) => {
  // fetch the DOM element of the buttons Container
  const container = document.getElementById('choosable-values-buttons')

  if (remove) {
    // empty the container
    container.innerHTML = ''
  } else {
    // for each choosable value create a button Element and add it to the container childrens
    getChoosableValues(data).forEach((value) => {
      const button = choosableValueButton(value)
      button.onclick = () => chooseValue(value, data)
      container.appendChild(button)
    })
  }
}
