import { createStore } from 'vuex'

export default createStore({
  state: {
    logged: true,
    user: {},
    meetings: [
      {
        title: 'Design Daily Zoom Meeting',
        startTime: Date.now(),
        endTime: Date.now(),
        users: [
          { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_1280.png' },
          { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://periculum.cuni.cz/wp-content/uploads/avatar.jpg' }
        ]
      }
    ]
  },
  mutations: {
  },
  actions: {
  },
  modules: {
  }
})
