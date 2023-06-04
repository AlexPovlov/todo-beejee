import { createSlice } from "@reduxjs/toolkit";

const initialState = {
  value: [],
};

export const todoSlice = createSlice({
  name: "todo",
  initialState,
  reducers: {
    setTodos: (state, todos) => {
      state.value = todos.payload;
    },
    add_todo: (state, todo) => {
      state.value.push(todo.payload);
    },
    delete_todo: (state, id) => {
      state.value = state.value.filter((el) => el.id !== id);
    },
    setComplete: (state, actions) => {
      const i = state.value.findIndex((obj) => obj.id === actions.payload.id);
      if (i !== -1) {
        state.value[i].complete = actions.payload.complete;
      }
    },
    editTodo: (state, actions) => {
      const i = state.value.findIndex((obj) => obj.id === actions.payload.id);
      if (i !== -1) {
        state.value[i] = actions.payload.todo;
      }
    },

    sortedTodo: (state, { payload }) => {
      // Определяем функцию сравнения в зависимости от выбранного поля сортировки
      const compareFn = (a, b) => {
        if (payload.direction == "asc") {
          if (a[payload.field] < b[payload.field]) {
            return -1;
          }
          if (a[payload.field] > b[payload.field]) {
            return 1;
          }
        } else if (payload.direction == "desc") {
          if (a[payload.field] < b[payload.field]) {
            return 1;
          }
          if (a[payload.field] > b[payload.field]) {
            return -1;
          }
        }

        return 0;
      };

      state.value.sort(compareFn);
    },
  },
});

// Action creators are generated for each case reducer function
export const {
  add_todo,
  delete_todo,
  setComplete,
  editTodo,
  setTodos,
  sortedTodo,
} = todoSlice.actions;

export default todoSlice.reducer;
