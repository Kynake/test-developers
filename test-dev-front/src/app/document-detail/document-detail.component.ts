//Imports Externos
import { Component, OnInit } from '@angular/core'
import { ActivatedRoute }    from '@angular/router'

//Imports Internos
import { Document }         from '../models/document.model'
import { DocumentsService } from '../backend/documents.service'
import { Response }         from '../models/response.model'
import { Signature }        from '../models/signature.model'

@Component({
  selector: 'app-document-detail',
  templateUrl: './document-detail.component.html',
  styleUrls: ['./document-detail.component.css']
})
export class DocumentDetailComponent implements OnInit {
  //Atributos
  id: number

  doc: Document = undefined

  isLoading: boolean = false
  hasError:  boolean = false

  isEditing: number = 0

  errorMessage: string

  //Construtor
  constructor(
    private route: ActivatedRoute,
    private document: DocumentsService
  ) { }

  //Métodos
  async ngOnInit() {
    this.isLoading = true

    this.id = this.route.snapshot.params.id

    let response: Response = await this.document.getDocument(this.id)

    if(!response.hasError) { 
      this.doc = response.data
    } else {
      this.hasError = true
      this.errorMessage = response.data
    }

    this.isLoading = false
  }

  createNewSignature() {
    this.doc.Signatures.push(undefined)
  }

  //Create
  appendToList(sig: Signature) {
    this.doc.Signatures[this.doc.Signatures.length - 1] = sig

    this.isEditing--
  }

  //Update
  updateExisting(sig: Signature) {
    for(let i = 0; i < this.doc.Signatures.length; i++) {
      if(this.doc.Signatures[i].id === sig.id) {
        this.doc.Signatures[i] = sig
        break
      }
    }

    this.isEditing--
  }

  //Delete
  clearSignatureFromList(id?: number) {
    if(id) { //Find ID in List
      for(let i = 0; i < this.doc.Signatures.length; i++) {
        if(this.doc.Signatures[i].id === id) {
          this.doc.Signatures.splice(i, 1)
          break
        }
      }
    } else { //Remove Last
      this.doc.Signatures.pop()
    }

    this.isEditing = this.isEditing == 0? 0 : this.isEditing - 1
  }

  setOrderingValue(checkUntil: number): number {
    if(checkUntil == 0) {
      return this.doc.Signatures[0]? this.doc.Signatures[0].ordering : 1
    } else {
      return this.doc.Signatures[checkUntil - 1].ordering + 1
    }
  }

  setIsEditing() {
    this.isEditing++
  }

  unsetIsEditing() {
    this.isEditing--
  }
}
