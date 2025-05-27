import { defineStore } from "pinia";
import axios from "axios";

export const useTodoStore = defineStore("todo", {
  state: () => ({
    todos: [],
  }),
  getters: {
    countTodos: (state) => state.todos.length,
  },
  actions: {
    async fetchTodos() {
      try {
        const response = await axios.get("http://localhost:3100/tasks");
        this.todos = response.data;
      } catch (error) {
        console.error("Failed to fetch todos:", error);
        // fallback to localStorage
        this.loadFromLocalStorage();
      }
    },
    toggleStatus(id) {
      const foundIndex = this.todos.findIndex((t) => t.id == id);
      if (foundIndex >= 0) {
        const todo = this.todos[foundIndex];
        todo.completedAt = todo.completedAt ? null : new Date().toISOString();
        this.saveToLocalStorage();
      }
    },
    addTodo(todo) {
      this.todos.push({
        id: Date.now(),
        name: todo,
        description: "description",
        createdAt: new Date().toISOString(),
        completedAt: null,
      });
      this.saveToLocalStorage();
    },
    clearAll() {
      this.todos = [];
      this.saveToLocalStorage();
    },
    saveToLocalStorage() {
      localStorage.setItem("todos", JSON.stringify(this.todos));
    },
    loadFromLocalStorage() {
      const stored = localStorage.getItem("todos");
      if (stored) {
        this.todos = JSON.parse(stored);
      }
    },
  },
});