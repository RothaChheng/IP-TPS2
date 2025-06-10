import { Injectable, NestMiddleware } from '@nestjs/common';
import { Request, Response, NextFunction } from 'express';

@Injectable()
export class LoggerMiddleware implements NestMiddleware {
  use(req: Request, res: Response, next: NextFunction) {
    const date = new Date().toISOString();
    const method = req.method;
    const path = req.originalUrl;
    console.log(`[${date}] ${method} ${path}`);
    next();
  }
}