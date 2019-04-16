//Imports Externos
import { Component, OnInit } from '@angular/core'
import { ActivatedRoute }    from '@angular/router'

//Imports Internos
import { Document }         from '../models/document.model'
import { DocumentsService } from '../backend/documents.service'
import { Response }         from '../models/response.model'

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
    console.log(this.id)

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

}
