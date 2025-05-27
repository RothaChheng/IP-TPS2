import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { User } from './user.entity';
import { Repository } from 'typeorm';

@Injectable()
export class UsersService {
  
  constructor(
    @InjectRepository(User)
    private usersRepo: Repository<User>,
  ) {}

  create(userData: Partial<User>) {
    const user = this.usersRepo.create(userData);
    const all = this.usersRepo.find({ relations: ['tasks'] })
    console.log(all)
    return this.usersRepo.save(user);
  }

  findAll() {
    console.log('hello')
    return this.usersRepo.find({ relations: ['tasks'] });
  }

  findOne(id: number) {
    return this.usersRepo.findOne({ where: { id }, relations: ['tasks'] });
  }

  async update(id: number, updateData: Partial<User>) {
    await this.usersRepo.update(id, updateData);
    return this.findOne(id);
  }

  async remove(id: number) {
    const user = await this.usersRepo.findOne({ where: { id },
      relations: ['tasks'],});
    return this.usersRepo.delete(id ).then(() => user);
  }
}


