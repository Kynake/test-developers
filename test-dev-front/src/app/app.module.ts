//Imports Externos
import { BrowserModule }                    from '@angular/platform-browser'
import { ReactiveFormsModule, FormsModule } from '@angular/forms' 
import { NgModule }                         from '@angular/core'
import { HttpClientModule }                 from '@angular/common/http'
import { NgbModule }                        from '@ng-bootstrap/ng-bootstrap'
import { AngularDraggableModule }           from 'angular2-draggable'

//Imports Internos
import { AppRoutingModule } from './app-routing.module'
import { AppComponent }     from './app.component'

//Serviços
import { DocumentsService }  from './backend/documents.service'
import { SignaturesService } from './backend/signatures.service';
import { DocumentsListComponent } from './documents-list/documents-list.component';
import { DocumentDetailComponent } from './document-detail/document-detail.component';
import { EditSignatureComponent } from './edit-signature/edit-signature.component'


@NgModule({
  declarations: [
    AppComponent,
    DocumentsListComponent,
    DocumentDetailComponent,
    EditSignatureComponent
  ],
  imports: [
    BrowserModule,
    ReactiveFormsModule,
    FormsModule,
    AppRoutingModule,
    HttpClientModule,
    NgbModule.forRoot(),
    AngularDraggableModule
  ],
  providers: [
    DocumentsService,
    SignaturesService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
