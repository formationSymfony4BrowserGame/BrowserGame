import axios from 'axios'
import htmlToElement from 'html-to-element'
import { throwDices } from './state/beginingState'
import { getChoosableValues, chooseValue } from './state/afterThrowState'
import { endTurn } from './state/beforeThrowState'
import { getChoosablePickomino, getStealablePlayer, chooseSkewerPickomino, stealPlayerPickomino } from './state/pickominoState'

// main render function
const render = (data) => {
  switch (data.state) {
    case 'loading':
      updateSkewer(data)
      updateRemainingDices(data)
      updateHand(data)
      updatePlayersScore(data)
      colorCurrentPlayerName(true, data)
      break
    case 'beginingState':
      modalSaveGame(true, data, 'save')
      setSaveButton(true, data, 'save-game')
      // Colorer le nom du joueur en cours
      colorCurrentPlayerName(true, data)
      // Activer le bouton "lancer"
      enableThrowButton(true, data)
      break
    case 'afterThrowState':
      modalSaveGame(true, data, 'save')
      setSaveButton(true, data, 'save-game')
      setEndTurnButton(false, data)
      // add buttons for each choosable values
      setChoosableValuesButtons(true, data)
      break
    case 'beforeThrowState':
      modalSaveGame(true, data, 'save')
      setSaveButton(true, data, 'save-game')
      if (data.remainingDices.length > 0) { // if there is dices left to throw
        // activate the throw button
        enableThrowButton(true, data)
        // show the endTurn button
        setEndTurnButton(true, data)
      }
      break
    case 'pickominoState':
      modalSaveGame(true, data, 'save')
      setSaveButton(true, data, 'save-game')
      // show and activate the choosable pickomino ( if any ) from the skewerPickomino
      setChoosablePickomino(data)
      // show and activate the stealable pickomino ( if any ) from the players
      setStealablePickomino(data)
      break
    case 'turnEndState':
      modalSaveGame(true, data, 'save')
      setSaveButton(true, data, 'save-game')
      // update dices and pickominos
      updateSkewer(data)
      updateRemainingDices(data)
      updateHand(data)
      updatePlayersScore(data)
      // uncolor current player name
      colorCurrentPlayerName(false, data)
  }
}
export default render

// html element definition
const pickominoElement = (pickomino) => htmlToElement(`
    <div class="pickomino column" id="pickomino-${pickomino}">
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
export const updateSkewer = (data) => {
  const skewer = document.getElementById('skewer')
  skewer.innerHTML = ''
  data.skewer.forEach(pickomino => {
    skewer.appendChild(pickominoElement(pickomino))
  })
}

export const updatePlayersScore = (data) => {
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
  const handScore = document.getElementById('hand-score-value')
  let score = 0
  hand.innerHTML = ''
  for (let i = 0; i < 8; i++) {
    const dice = data.hand[i]
    if (dice !== undefined) {
      hand.appendChild(diceElement(dice))
      score += (dice === 6 ? 5 : dice)
    } else {
      hand.appendChild(emptyDiceElement())
    }
  }
  handScore.innerHTML = score
}

const colorCurrentPlayerName = (value, data) => {
  // récuperer l'objet du DOM
  const currentPlayerName = document.getElementById(data.currentPlayer + '-name')
  if (value) {
    // ajouter la class de Bulma pour changer la couleur
    Array.from(currentPlayerName.children).forEach((child) => child.classList.add('has-text-info'))
  } else {
    // remove bulma color class
    Array.from(currentPlayerName.children).forEach((child) => child.classList.remove('has-text-info'))
  }
}

// value dertermine if the button should be enabled or not (false = disabled, true = enabled)
export const enableThrowButton = (value, data) => {
  // récuperer l'objet du DOM
  const throwButton = document.getElementById('button')
  // activer ou désactiver le boutton
  if (value) {
    throwButton.removeAttribute('disabled')
    throwButton.onclick = () => throwDices(data)
  } else {
    throwButton.setAttribute('disabled', '')
    throwButton.onclick = null
  }
}

// set the content of the Choosable Values Buttons container
// value dertermine if the content should be empty or not (false = empty, true = not empty)
export const setChoosableValuesButtons = (value, data) => {
  // fetch the DOM element of the buttons Container
  const container = document.getElementById('choosable-values-buttons')

  if (value) {
    // for each choosable value create a button Element and add it to the container childrens
    getChoosableValues(data).forEach((value) => {
      const button = choosableValueButton(value)
      button.onclick = () => chooseValue(value, data)
      container.appendChild(button)
    })
  } else {
    // empty the container
    container.innerHTML = ''
  }
}

// value dertermine if the content should be empty or not (false = empty, true = not empty)
const setEndTurnButton = (value, data) => {
  const endTurnButton = document.getElementById('end-turn-button')
  if (value) {
    endTurnButton.classList.add('visible')
    endTurnButton.onclick = () => {
      enableThrowButton(false, data)
      setEndTurnButton(false, data)
      endTurn(data)
    }
  } else {
    endTurnButton.classList.remove('visible')
    endTurnButton.onclick = null
  }
}

const setChoosablePickomino = (data) => {
  // getting the choosable pickomino value
  const choosablePickomino = getChoosablePickomino(data)
  if (!isNaN(choosablePickomino) && choosablePickomino !== -1) {
    // fetching the DOM Element of that pickomino
    const choosablePickominoElement = document.getElementById('pickomino-' + choosablePickomino)
    choosablePickominoElement.classList.add('choosable')
    choosablePickominoElement.onclick = () => chooseSkewerPickomino(choosablePickomino, data)
  }
}

const setStealablePickomino = (data) => {
  // getting the stealable player
  const stealablePlayerRanking = getStealablePlayer(data)
  if (stealablePlayerRanking !== null) {
    const stealablePickominoElement = document.getElementById(stealablePlayerRanking + '-score').children[1]
    stealablePickominoElement.classList.add('choosable')
    stealablePickominoElement.onclick = () => stealPlayerPickomino(stealablePlayerRanking, data)
  }
}

const setSaveButton = (value, data, id) => {
  const saveButton = document.getElementById(id)
  if (value) {
    saveButton.onclick = () => {
      axios({
        method: 'post',
        url: '/save',
        data: JSON.stringify(data)
      })
      setSaveButton(false, data)
    }
    saveButton.removeAttribute('disabled')
  } else {
    saveButton.onclick = null
    saveButton.setAttribute('disabled', '')
  }
}
// Modal creation
const modalElement = (value) => htmlToElement(`
<div class="modal" id="showmodal">
  <div class="modal-background"></div>
  <div class="modal-content">
      <div class="section modal-wrap" >
          <div class="column has-text-centered">
              <p>Voulez vous sauvegarder la partie avant de quitter le jeu?</p>
              <a href="/save" class="button is-success mt-2" id="save" >OUI, je sauvegarde</a>
              <a href="${value}" class="button is-danger mt-2">Non, merci</a>
          </div>
      </div>
  </div>
  <button class="modal-close is-large" aria-label="close" id="cancel"></button>
</div>`
)
// function to bring up the modal
const modalSaveGame = (valeur, data, id) => {
  // all internal links of the app
  const anchorTemplate = document.getElementsByClassName('anchorTemplate')
  // empty div
  const savemodal = document.getElementById('saveModal')

  // for each element having the class anchorTemplate
  for (let i = 0, len = anchorTemplate.length; i < len; i++) {
    // open modal
    anchorTemplate[i].onclick = () => {
      savemodal.appendChild(modalElement(anchorTemplate[i].value))
      const modal = document.getElementById('showmodal')
      modal.style.display = 'block'
      //
      setSaveButton(valeur, data, id)
      // close modal
      const close = document.getElementById('cancel')
      close.onclick = () => {
        const modal = document.getElementById('showmodal')
        modal.style.display = 'none'
      }
    }
  }
}
