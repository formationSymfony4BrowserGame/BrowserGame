import render, { updateHand, updateRemainingDices } from '../render'
import turnEndState from './turnEndState'
import beforeThrowState from './beforeThrowState'

// take in data Object and return all the dice values from a throw that isn't in the hand and thus choosable
export const getChoosableValues = (data) => {
  const throwValues = new Set(data.remainingDices) // tranforming to a set remove duplicate values
  const handValues = new Set(data.hand)
  return Array.from(throwValues).filter((value) => !handValues.has(value)) // tranforming back to an array to use map() to iterate over throwValues
}

export const chooseValue = (value, data) => {
  // add dices to hand
  data.remainingDices.forEach((dice) => {
    if (dice === value) {
      data.hand.push(dice)
    }
  })
  // remove dices from remainingDices
  data.remainingDices = data.remainingDices.filter((dice) => dice !== value) //
  // call render function for remainingDices and hand
  updateHand(data)
  updateRemainingDices(data)
  // call next state
  beforeThrowState(data)
}

const afterThrowState = (data) => {
  data.state = 'afterThrowState'
  render(data)
  if (getChoosableValues(data).length === 0) {
    turnEndState(data)
  }
}

export default afterThrowState
