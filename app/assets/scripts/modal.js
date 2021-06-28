const button = document.getElementById('button')
const modal = document.getElementById('page-modal')
const cancel = document.getElementById('close')

const displayModal = () => {
  modal.style.display = 'block'
}

const closeModal = () => {
  modal.style.display = 'none'
}

button.onclick = displayModal
cancel.onclick = closeModal
