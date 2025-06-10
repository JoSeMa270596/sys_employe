import { Component, inject } from '@angular/core';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss'
})
export class LoginComponent {
  #authService = inject(AuthService);
  #router = inject(Router);
  email = '';
  password = '';
  errorMessage = '';

  onSubmit() {
    this.#authService.login({ email: this.email, password: this.password }).subscribe({
      next: (res: any) => {
        this.#authService.saveToken(res.access_token);
        this.#router.navigate(['/dashboard']);
      },
      error: (err) => {
        this.errorMessage = 'Credenciales inválidas';
      }
    });
  }

}
