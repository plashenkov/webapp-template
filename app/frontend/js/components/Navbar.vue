<template>
  <div class="navbar">
    <router-link to="/" exact>Main</router-link>
    <router-link to="/about">About</router-link>
  </div>
</template>

<style lang="scss">
  .navbar {
    text-align: center;
    margin-bottom: 40px;

    > a {
      color: #333;
      display: inline-block;
      padding: 0 20px;
      line-height: 40px;
      border-radius: 20px;
      text-decoration: none;
      transition: background-color 0.3s ease;

      &:hover {
        background: #ddd;
      }
    }

    > a.router-link-active {
      background: #ccc;
    }
  }
</style>
