import { Routes } from '@angular/router';
import { DepartmentComponent } from './features/department/department.component';
import { EmployeeComponent } from './features/employee/employee.component';
import { LoginComponent } from './features/auth/login/login.component';
import { authGuard } from './features/auth/auth.guard';

export const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: 'department', component: DepartmentComponent, canActivate: [authGuard] },
  { path: 'employee', component: EmployeeComponent, canActivate: [authGuard] },
  { path: '', redirectTo: '/employee', pathMatch: 'full' },
  { path: '**', redirectTo: '/employee' }
];
