import { HttpClient, HttpHeaders } from '@angular/common/http';
import { inject, Injectable } from '@angular/core';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  #apiUrl = 'http://localhost:8000/api';
  #httpClient = inject(HttpClient);
  #router = inject(Router);

  login(credentials: { email: string; password: string }) {
    return this.#httpClient.post(`${this.#apiUrl}/login`, credentials);
  }

  getUser() {
    const token = this.getToken();
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`
    });
    return this.#httpClient.get(`${this.#apiUrl}/user`, { headers });
  }

  logout() {
    const token = this.getToken();
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`
    });
    return this.#httpClient.post(`${this.#apiUrl}/logout`, {}, { headers });
  }

  saveToken(token: string) {
    localStorage.setItem('access_token', token);
  }

  getToken(): string | null {
    return localStorage.getItem('access_token');
  }

  isLoggedIn(): boolean {
    return !!this.getToken();
  }

  clearSession() {
    localStorage.removeItem('access_token');
    this.#router.navigate(['/login']);
  }
}
