import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { DepartmentService } from './department.service';
import { Department } from './models/department';
import { CreateDepartmentDto } from './models/create-department-dto';
import { UpdateDepartmentDto } from './models/update-department-dto';

@Component({
  selector: 'app-department',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './department.component.html',
  styleUrl: './department.component.scss'
})
export class DepartmentComponent {
  #departmentService = inject(DepartmentService);

  departments: Department[] = [];
  newDepartment: CreateDepartmentDto = { name: '' };
  editingDepartment: Department | null = null;

  ngOnInit(): void {
    this.loadDepartments();
  }

  loadDepartments(): void {
    this.#departmentService.getDepartments().subscribe({
      next: (data) => {
        this.departments = data;
        console.log('Departamentos cargados:', this.departments);
      },
      error: (err) => {
        console.error('Error al cargar departamentos:', err);
      }
    });
  }

  saveDepartment(): void {
    if (this.editingDepartment) {
      const updateData: UpdateDepartmentDto & { id: number } = {
        id: this.editingDepartment.id,
        name: this.newDepartment.name
      };

      this.#departmentService.updateDepartment(updateData).subscribe({
        next: (updatedDept) => {
          console.log('Departamento actualizado:', updatedDept);
          this.resetForm();
          this.loadDepartments();
        },
        error: (err) => {
          console.error('Error al actualizar departamento:', err);
        }
      });
    } else {
      this.#departmentService.createDepartment(this.newDepartment).subscribe({
        next: (createdDept) => {
          console.log('Departamento creado:', createdDept);
          this.resetForm();
          this.loadDepartments();
        },
        error: (err) => {
          console.error('Error al crear departamento:', err);
        }
      });
    }
  }

  editDepartment(department: Department): void {
    this.editingDepartment = { ...department };
    this.newDepartment = { name: department.name };
  }

  deleteDepartment(id: number): void {
    if (confirm('¿Estás seguro de que quieres eliminar este departamento? Ten en cuenta que si tiene empleados asociados, la API podría impedir la eliminación.')) {
      this.#departmentService.deleteDepartment(id).subscribe({
        next: (response) => {
          console.log(response.message);
          this.departments = this.departments.filter(dept => dept.id !== id);
        },
        error: (err) => {
          console.error('Error al eliminar departamento:', err);
          alert('Error al eliminar departamento: ' + (err.error.message || 'Error desconocido'));
        }
      });
    }
  }

  cancelEdit(): void {
    this.resetForm();
  }

  resetForm(): void {
    this.newDepartment = { name: '' };
    this.editingDepartment = null;
  }

  assignChief(departmentId: number, chiefEmployeeId: number): void {
    this.#departmentService.assignChief(departmentId, chiefEmployeeId).subscribe({
      next: (response) => {
        console.log(response.message);
        this.loadDepartments();
      },
      error: (err) => {
        console.error('Error al asignar jefe:', err);
        alert('Error al asignar jefe: ' + (err.error.message || 'Error desconocido'));
      }
    });
  }
}
