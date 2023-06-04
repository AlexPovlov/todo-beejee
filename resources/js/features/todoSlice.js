import { createSlice } from "@reduxjs/toolkit";

const initialState = {
  value: [],
};

export const todoSlice = createSlice({
  name: "todo",
  initialState,
  reducers: {
    setTodos: (state, { payload }) => {
      state.value = payload;
    },
    add_todo: (state, { payload }) => {
      state.value.push(payload);
    },
    delete_todo: (state, { payload }) => {
      console.log(payload);
      state.value = state.value.filter((obj) => obj.id !== payload);
    },
    setComplete: (state, { payload }) => {
      const i = state.value.findIndex((obj) => obj.id === payload.id);
      if (i !== -1) {
        state.value[i].complete = payload.complete;
      }
    },
    editTodo: (state, { payload }) => {
      const i = state.value.findIndex((obj) => obj.id === payload.id);
      if (i !== -1) {
        state.value[i] = payload.todo;
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
