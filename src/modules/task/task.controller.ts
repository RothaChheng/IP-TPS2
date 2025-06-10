import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  Patch,
  Post,
  UsePipes,
  ValidationPipe,
} from '@nestjs/common';
import { TasksService } from './task.service';
import { CreateTaskDto } from './dto/create-task.dto';

@Controller('tasks')
export class TasksController {
  constructor(private readonly taskService: TasksService) {}


  // @Post()
  // createTask(@Body() body: any) {
  //   return this.taskService.create(body);
  // }

  @Post()
  @UsePipes(new ValidationPipe({ whitelist: true }))
  create(@Body() createTaskDto: CreateTaskDto) {
    return this.taskService.create(createTaskDto);
  }

  @Get()
  getAllTasks() {
    return this.taskService.findAll();
  }

  @Get(':id')
  getTaskById(@Param('id') id: number) {
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
