import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core'
import { FormGroup, FormBuilder, Validators }             from '@angular/forms'
import * as moment                                        from 'moment'

import { Signature } from '../models/signature.model';
import { SignaturesService } from '../backend/signatures.service';
import { Response } from '../models/response.model';

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

  @Output() onCreate:  EventEmitter<Signature> = new EventEmitter<Signature>()
  @Output() onUpdate:  EventEmitter<Signature> = new EventEmitter<Signature>()
  @Output() onDelete:  EventEmitter<number>    = new EventEmitter<number>()

  @Output() onCancel:  EventEmitter<void> = new EventEmitter<void>()
  @Output() onEditing: EventEmitter<void> = new EventEmitter<void>()

  isEditing: boolean = false
  editForm: FormGroup

  isLoading: boolean = false
  hasError:  boolean = false

  errorMessage: string

  //Construtor
  constructor(
    private formBuilder: FormBuilder,
    private signaturesService:  SignaturesService
  ) {}

  //Métodos
  ngOnInit() {
    this.isLoading = true

    moment.locale('pt-BR')

    const sig = this.signature
    if(sig) {
      sig.timestamp = moment(sig.timestamp).format('YYYY-MM-DD HH:mm:ss')
    }

    //Cria inputs e Forms
    this.editForm = this.formBuilder.group({
      id_document: this.formBuilder.control(sig? sig.id_document      : this.id_document,                          [Validators.required]),
      ordering:    this.formBuilder.control(sig? sig.ordering         : this.order,                                [Validators.required]),
      name:        this.formBuilder.control(sig? sig.name             : null,                                      [Validators.required]),
      issuer:      this.formBuilder.control(sig? sig.issuer           : null,                                      [Validators.required]),
      timestamp:   this.formBuilder.control(moment(sig? sig.timestamp : new Date()).format('YYYY-MM-DD HH:mm:ss'), [Validators.required])
    })

    this.isEditing = !this.editForm.valid
    if(this.isEditing) {
      this.onEditing.emit()
    }

    this.isLoading = false
  }

  //POST Method
  async postSignature() {
    this.isLoading = true

    let sig: Signature = {
      id_document: this.editForm.value.id_document,
      issuer:      this.editForm.value.issuer,
      name:        this.editForm.value.name,
      ordering:    this.editForm.value.ordering,
      timestamp:   this.editForm.value.timestamp
    }

    let response: Response = await this.signaturesService.postSignature(sig)

    if(!response.hasError) {
      this.signature = response.data
      this.updateForm()

      this.onCreate.emit(this.signature)
    }

    this.isLoading = false
  }

  //PUT Method
  async putSignature() {
    this.isLoading = true

    let sig: Signature = {
      id:          this.signature.id,
      id_document: this.editForm.value.id_document,
      issuer:      this.editForm.value.issuer,
      name:        this.editForm.value.name,
      ordering:    this.editForm.value.ordering,
      timestamp:   this.editForm.value.timestamp
    }

    let response: Response = await this.signaturesService.putSignature(sig)

    if(!response.hasError) {
      this.signature = response.data
      this.updateForm()

      this.onUpdate.emit(this.signature)
    }

    this.isLoading = false
  }

  //DELETE Method
  async deleteSignature() {
    this.isLoading = true

    if(this.signature) { //DELETE on Backend
      let response: Response = await this.signaturesService.deleteSignature(this.signature.id)

      if(!response.hasError) {
        this.onDelete.emit(this.signature.id)
      }
    } else { //NEW Signature, not on Backend
      this.onDelete.emit(undefined)
    }

    this.isLoading = false
  }

  updateForm() {
    this.editForm.setValue({
      id_document: this.signature.id_document,
      ordering:    this.signature.ordering,
      name:        this.signature.name,
      issuer:      this.signature.issuer,
      timestamp:   this.signature.timestamp
    })
  }

  toggleEdit() {
    this.isEditing = !this.isEditing

    if(this.isEditing) {
      this.onEditing.emit()
    } else {
      if(this.editForm.dirty) {
        this.editForm.markAsPristine()
      
        if(this.signature) {
          this.putSignature()
        } else {
          this.postSignature()
        }
      } else {
        this.onCancel.emit()
      }
    }

  }

}
