//Imports Externos
import { Component, OnInit } from '@angular/core'

//Imports Internos
import { Document }         from '../models/document.model'
import { DocumentsService } from '../backend/documents.service'
import { Response }         from '../models/response.model'

@Component({
  selector: 'app-documents-list',
  templateUrl: './documents-list.component.html',
  styleUrls: ['./documents-list.component.css']
})
export class DocumentsListComponent implements OnInit {
  //Atributos
  documentsList: Document[]

  isLoading: boolean = false
  hasError:  boolean = false

  errorMessage: string

  //Construtor
  constructor(
    private documents: DocumentsService
  ) {}

  //Métodos
  async ngOnInit() {
    console.log('ON DOCUMENTS LIST COMPONENT')
    this.isLoading = true

    let response: Response = await this.documents.getDocuments()

    if(!response.hasError) { 
      this.documentsList = response.data
    } else {
      this.hasError = true
      this.errorMessage = response.data
    }

    this.isLoading = false
  }

}
