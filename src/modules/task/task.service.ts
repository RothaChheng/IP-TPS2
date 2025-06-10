import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Task } from './task.entity';

@Injectable()
export class TasksService {
  tasks: any;

  constructor(
    @InjectRepository(Task)
    private tasksRepo: Repository<any>,
  ) {}

  create(taskData: Partial<Task>) {
    const task = this.tasksRepo.create(taskData);
    return this.tasksRepo.save(task);
  }

  findAll() {
    return this.tasksRepo.find({ relations: ['user'] });
  }

  async findOne(id: number) {
    const task = await this.tasksRepo.findOne({
      where: { id: id },
      relations: ['user'],
    });
    
    if (!task) {
      throw new NotFoundException(`Task with id ${id} not found`);
    }
    return task;
  }

  async update(id: number, updateData: Partial<Task>) {
    await this.tasksRepo.update(id, updateData);
    return this.findOne(id);
  }

  async remove(id: number) {
    const task = await this.tasksRepo.findOne({ where: { id },
      relations: ['user'],});
    return this.tasksRepo.delete(id).then(() => task);
  }
}
