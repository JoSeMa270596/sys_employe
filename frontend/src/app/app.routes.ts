import { Routes } from '@angular/router';
import { DepartmentComponent } from './features/department/department.component';
import { EmployeeComponent } from './features/employee/employee.component';

export const routes: Routes = [
  { path: 'department', component: DepartmentComponent },
  { path: 'employee', component: EmployeeComponent },
  { path: '', redirectTo: '/employee', pathMatch: 'full' },
  { path: '**', redirectTo: '/employee' }
];
