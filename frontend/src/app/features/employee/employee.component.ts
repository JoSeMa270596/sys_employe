import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { EmployeeService } from './employee.service';
 import { Employee } from './models/employee';
import { CreateEmployeeDto } from './models/create-employee-dto';
import { UpdateEmployeeDto } from './models/update-employee-dto';
import { ApiResponse } from '../../shared/models/api-response';
import { StatusEnum } from '../../shared/models/status-enum';

@Component({
  selector: 'app-employee',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './employee.component.html',
  styleUrl: './employee.component.scss'
})
export class EmployeeComponent {
  #employeeService = inject(EmployeeService);

  employeesResponse: ApiResponse<Employee[]> | null = null;
  employees: Employee[] = [];
  newEmployee: CreateEmployeeDto = {
    user_id: 0,
    first_name: '',
    last_name: '',
    department_id: 0,
    hire_date: '',
    status: StatusEnum.ACTIVE
  };
  editingEmployee: Employee | null = null;
  currentFilters = {
    name: '',
    department_id: 0,
    status: '',
    hire_date: '',
    page: 1
  };
  statusOptions = Object.values(StatusEnum).map(value => ({
    label: (value.replace(/_/g, ' ')),
    value: value
  }));
  currentStatusEnum = StatusEnum;

  ngOnInit(): void {
    this.loadEmployees();
  }
  loadEmployees(): void {
    this.#employeeService.getEmployees({
      name: this.currentFilters.name || undefined,
      department_id: this.currentFilters.department_id || undefined,
      status: this.currentFilters.status as 'activo' | 'inactivo' || undefined,
      hire_date: this.currentFilters.hire_date || undefined,
      page: this.currentFilters.page
    }).subscribe({
      next: (response) => {
        this.employeesResponse = response;
        this.employees = response.data;
      },
      error: (err) => {
        console.error('Error al cargar empleados:', err);
      }
    });
  }

  saveEmployee(): void {
    if (this.editingEmployee) {
      // Editar empleado existente
      const updateData: UpdateEmployeeDto & { id: number } = {
        id: this.editingEmployee.id,
        first_name: this.newEmployee.first_name,
        last_name: this.newEmployee.last_name,
        department_id: this.newEmployee.department_id,
        hire_date: this.newEmployee.hire_date,
        status: this.newEmployee.status
      };

      this.#employeeService.updateEmployee(updateData).subscribe({
        next: () => {
          this.resetForm();
          this.loadEmployees();
        },
        error: (err) => {
          console.error('Error al actualizar empleado:', err);
        }
      });
    } else {
      // Crear nuevo empleado
      this.#employeeService.createEmployee(this.newEmployee).subscribe({
        next: () => {
          this.resetForm();
          this.loadEmployees();
        },
        error: (err) => {
          console.error('Error al crear empleado:', err);
        }
      });
    }
  }

  editEmployee(employee: Employee): void {
    this.editingEmployee = { ...employee };
    this.newEmployee = {
      user_id: employee.user_id,
      first_name: employee.first_name,
      last_name: employee.last_name,
      department_id: employee.department_id,
      hire_date: employee.hire_date,
      status: employee.status
    };
  }

  deleteEmployee(id: number): void {
    if (confirm('¿Estás seguro de que quieres eliminar este empleado?')) {
      this.#employeeService.deleteEmployee(id).subscribe({
        next: () => {
          this.employees = this.employees.filter(employee => employee.id !== id);
          if (this.employeesResponse) {
            this.employeesResponse.data = this.employeesResponse.data.filter(employee => employee.id !== id);
            this.employeesResponse.total--;
          }
        },
        error: (err) => {
          console.error('Error al eliminar empleado:', err);
        }
      });
    }
  }

  cancelEdit(): void {
    this.resetForm();
  }

  resetForm(): void {
    this.newEmployee = {
      user_id: 0,
      first_name: '',
      last_name: '',
      department_id: 0,
      hire_date: '',
      status: StatusEnum.ACTIVE
    };
    this.editingEmployee = null;
  }

  changePage(page: number): void {
    this.currentFilters.page = page;
    this.loadEmployees();
  }

  applyFilters(): void {
    this.currentFilters.page = 1;
    this.loadEmployees();
  }

  clearFilters(): void {
    this.currentFilters = {
      name: '',
      department_id: 0,
      status: '',
      hire_date: '',
      page: 1
    };
    this.loadEmployees();
  }

  getPagesArray(response: ApiResponse<Employee[]>): number[] {
    const pages = [];
    const maxVisiblePages = 5;
    let startPage = Math.max(1, response.current_page - Math.floor(maxVisiblePages / 2));
    const endPage = Math.min(response.last_page, startPage + maxVisiblePages - 1);

    // Ajustar si estamos cerca del final
    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
      pages.push(i);
    }

    return pages;
  }

}
