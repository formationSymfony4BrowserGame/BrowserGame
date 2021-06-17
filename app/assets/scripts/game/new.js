import htmlToElement from 'html-to-element'

const select = document.getElementById('select-joueurs')
const nameForm = document.getElementById('other-players')
const names = ['', '', '', '', '', ''] // store entered names to prevent data loose when changing player count

// return a HTMLElement containing a player name field
const nameField = (playerNumber, name) => htmlToElement(`
<div class="field is-horizontal">
  <label for="${playerNumber}" class="label">Joueur ${playerNumber}</label>
  <div class="control">
    <input type="text" class="input" id="${playerNumber}" name="${playerNumber}" value="${name}">
  </div>
</div>
`)

// update names
const updateNames = (e) => { names[e.target.id - 2] = e.target.value }

// add the desired number of player name inputs in DOM
const updateNewGameForm = () => {
  const playerCount = select.value
  // remove all player name fields except the first
  nameForm.innerHTML = ''
  // i∈[2,playerCount], add playerCount-1 times a player name field to the form
  for (let i = 2; i <= playerCount; i++) {
    const element = nameField(i, names[i - 2])
    element.oninput = updateNames // add the updateNames EventHandler to the oninput event of the name fields
    nameForm.appendChild(element)
  }
}

updateNewGameForm() // set the form for the initial player count
select.onchange = updateNewGameForm // update the form when the player count is changed
