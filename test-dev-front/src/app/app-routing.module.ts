import { NgModule }                from '@angular/core'
import { Routes, RouterModule }    from '@angular/router'
import { DocumentsListComponent }  from './documents-list/documents-list.component'
import { DocumentDetailComponent } from './document-detail/document-detail.component'

const routes: Routes = [
  { path: '',              component: DocumentsListComponent  },
  { path: 'documents',     component: DocumentsListComponent  },
  { path: 'documents/:id', component: DocumentDetailComponent }
]

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
