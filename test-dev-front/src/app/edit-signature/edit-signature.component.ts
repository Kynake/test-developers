import { Component, OnInit, Input }           from '@angular/core'
import { FormGroup, FormBuilder, Validators } from '@angular/forms'

import { Signature } from '../models/signature.model';

@Component({
  selector: 'app-edit-signature',
  templateUrl: './edit-signature.component.html',
  styleUrls: ['./edit-signature.component.css']
})
export class EditSignatureComponent implements OnInit {
  //Atributos
  @Input() signature:  Signature
  @Input() id_document: number
  @Input() order:      number

  isEditing: boolean = false
  editForm: FormGroup

  isLoading: boolean = false
  hasError:  boolean = false

  errorMessage: string

  //Construtor
  constructor(
    private formBuilder: FormBuilder
  ) {}

  //Métodos
  ngOnInit() {
    console.log(this.signature)
    console.log(this.id_document)
    console.log(this.order)

    this.isLoading = true

    const sig = this.signature

    //Cria inputs e Forms
    this.editForm = this.formBuilder.group({
      id_document: this.formBuilder.control(sig? sig.id_document : this.id_document, [Validators.required]),
      ordering:    this.formBuilder.control(sig? sig.ordering    : this.order,       [Validators.required]),
      name:        this.formBuilder.control(sig? sig.name        : null,             [Validators.required]),
      issuer:      this.formBuilder.control(sig? sig.issuer      : null,             [Validators.required]),
      timestamp:   this.formBuilder.control(sig? sig.timestamp   : new Date(),       [Validators.required])
    })

    this.isEditing = !this.editForm.valid

    this.isLoading = false
  }

  toggleEdit() {
    this.isEditing = !this.isEditing

    if(!this.isEditing && this.editForm.dirty) {
      this.editForm.markAsPristine()
      
      console.log('UPDATE API INFO EVENT')
    }
  }

}
