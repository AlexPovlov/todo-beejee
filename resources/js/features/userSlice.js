import { createSlice } from "@reduxjs/toolkit";

const initialState = {
  data: {},
  isAuth: false,
};

export const userSlice = createSlice({
  name: "user",
  initialState,
  reducers: {
    setAuth: (state, { payload }) => {
      state.data = payload;
      state.isAuth = true;
    },

    delAuth: (state) => {
      state.data = {};
      state.isAuth = false;
    },
  },
});

// Action creators are generated for each case reducer function
export const {
    setAuth,
    delAuth
} = userSlice.actions;

export default userSlice.reducer;
