//Imports Externos
import { Injectable } from '@angular/core'
import { HttpClient } from '@angular/common/http'

//Imports Internos
import { APIResponse, APIPath } from './backend.config'
import { Response }             from '../models/response.model'

@Injectable({
  providedIn: 'root'
})
export class DocumentsService {
  //Construtor
  constructor(private http: HttpClient) {}

  //Métodos

  //GETs
  async getDocuments(): Promise<Response> {
    let apiResponse: APIResponse = await this.http
      .get<APIResponse>(`${APIPath}/documents`).toPromise()

    // console.log('Documents Service: GET Documents response: ', apiResponse)

    return {
      hasError: apiResponse.status !== 'OK',
      data: apiResponse.data
    }
  }

  async getDocument(id: number): Promise<Response> {
    let apiResponse: APIResponse = await this.http
      .get<APIResponse>(`${APIPath}/documents/${id}`).toPromise()

    // console.log('Documents Service: GET Document by ID response: ', apiResponse)

    return {
      hasError: apiResponse.status !== 'OK',
      data: apiResponse.data
    }
  }
}
