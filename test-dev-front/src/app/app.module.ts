//Imports Externos
import { BrowserModule }    from '@angular/platform-browser'
import { NgModule }         from '@angular/core'
import { HttpClientModule } from '@angular/common/http'

//Imports Internos
import { AppRoutingModule } from './app-routing.module'
import { AppComponent }     from './app.component'

//Serviços
import { DocumentsService }  from './backend/documents.service'
import { SignaturesService } from './backend/signatures.service'

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule
  ],
  providers: [
    DocumentsService,
    SignaturesService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
