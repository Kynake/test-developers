import { Signature } from './signature.model'

export interface Document {
  id:         number,

  name:       string,
  content:    string,
  timestamp:  string,

  Signatures?: Signature[]
}