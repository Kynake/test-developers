//Imports Externos
import { Injectable }              from '@angular/core'
import { HttpClient, HttpHeaders } from '@angular/common/http'

//Imports Internos
import { APIResponse, APIPath } from './backend.config'
import { Response }             from '../models/response.model'
import { Signature }            from '../models/signature.model'

@Injectable({
  providedIn: 'root'
})
export class SignaturesService {
  //Construtor
  constructor(private http: HttpClient) {}

  //Métodos

  //GETs
  async getSignatures(): Promise<Response> {
    let apiResponse: APIResponse = await this.http
      .get<APIResponse>(`${APIPath}/signatures`).toPromise()

    // console.log('Signatures Service: GET Signatures response: ', apiResponse)

    return {
      hasError: apiResponse.status !== 'OK',
      data: apiResponse.data
    }
  }

  async getSignature(id: number): Promise<Response> {
    let apiResponse: APIResponse = await this.http
      .get<APIResponse>(`${APIPath}/signatures/${id}`).toPromise()

    // console.log('Signatures Service: GET Signature by ID response: ', apiResponse)

    return {
      hasError: apiResponse.status !== 'OK',
      data: apiResponse.data
    }
  }

  //POST
  async postSignature(sig: Signature): Promise<Response> {
    const headers: HttpHeaders = new HttpHeaders({ 'Content-Type': 'application/json' })

    let apiResponse: APIResponse = await this.http
      .post<APIResponse>(`${APIPath}/signatures`, sig, { headers: headers }).toPromise()

    // console.log('Signatures Service: POST Signature response: ', apiResponse)

    return {
      hasError: apiResponse.status !== 'OK',
      data: apiResponse.data
    }
  }

  //PUT
  async putSignature(sig: Signature): Promise<Response> {
    const headers: HttpHeaders = new HttpHeaders({ 'Content-Type': 'application/json' })

    let apiResponse: APIResponse = await this.http
      .put<APIResponse>(`${APIPath}/signatures`, sig, { headers: headers }).toPromise()

    // console.log('Signatures Service: PUT Signature response: ', apiResponse)

    return {
      hasError: apiResponse.status !== 'OK',
      data: apiResponse.data
    }
  }

  //DELETE
  async deleteSignature(id: number): Promise<Response> {
    let apiResponse: APIResponse = await this.http
      .delete<APIResponse>(`${APIPath}/signatures/${id}`).toPromise()

    // console.log('Signatures Service: DELETE Signature response: ', apiResponse)

    return {
      hasError: apiResponse.status !== 'OK',
      data: apiResponse.data
    }
  }
}
