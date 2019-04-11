import { Document } from './document.model'

export interface Signature {
  //Ausente no POST, Obrigatório no PUT
  id?:         number,

  id_document: number,
  ordering:    number,

  name:        string,
  issuer:      string,
  timestamp:   string,

  Document?:   Document
}