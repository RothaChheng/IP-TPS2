import {
  Get,
  Param,
  Controller,
  Post,
  Body,
  Patch,
  Delete,
} from '@nestjs/common';
import { UsersService } from './user.service';
import { User } from './user.entity';

@Controller('users')
export class UsersController {
  constructor(private readonly userService: UsersService) {}

  @Get() 
  getAllUsers() {
    return this.userService.findAll();
  }

  @Get(':id')
  getUserById(@Param('id') id: string) {
    return this.userService.findOne(+id);
  }

  @Post()
  createUser(@Body() body: Partial<User>) {
    return this.userService.create(body);
  }

  @Patch(':id')
  updateUser(
    @Param('id') id: string,
    @Body() body: Partial<User>,
  ) { 
    return this.userService.update(+id, body);
  }

  @Delete(':id')
  deleteUser(@Param('id') id: string) {
    return this.userService.remove(+id);
  }
}
