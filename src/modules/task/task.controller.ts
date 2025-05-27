import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  Patch,
  Post,
} from '@nestjs/common';
import { TasksService } from './task.service';

@Controller('tasks')
export class TasksController {
  constructor(private readonly taskService: TasksService) {}


  @Post()
  createTask(@Body() body: any) {
    return this.taskService.create(body);
  }

  @Get()
  getAllTasks() {
    return this.taskService.findAll();
  }

  @Get(':id')
  getTaskById(@Param('id') id: string) {
    return this.taskService.findOne(+id);
  }

  @Patch(':id')
  updateTask(
    @Param('id') id: string,
    @Body() body: any,
  ) {
    return this.taskService.update(+id, body);
  }

  @Delete(':id')
  deleteTask(@Param('id') id: string) {
    return this.taskService.remove(+id);
  }
}
