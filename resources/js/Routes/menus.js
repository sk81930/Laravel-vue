

import store from '../Store';

let adminMenus = [];
adminMenus.push(
    {
      title: "Dashboard",
      href: "/admin/dashboard",
    },
    {
      title: "Profile",
      href: "/admin/profile",
    },
    {
      title: "Users",
      href: "/admin/users",
    },
);



/* Manager menu */

let managerMenus = [];
managerMenus.push(
    {
      title: "Dashboard",
      href: "/manager/dashboard",
    },
    {
      title: "Projects",
      href: "/manager/projects",
    },
    {
      title: "Tasks",
      href: "/manager/tasks",
    },
    {
      title: "Profile",
      href: "/manager/profile",
    },
);

export {managerMenus,adminMenus};