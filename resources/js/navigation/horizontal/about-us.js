export default [
  {
    title: "About Us",
    icon: { icon: "tabler-mail" },
    children: [
      {
        title: "History",
        to: { name: "about-us-id", params: { id: 1 } },
        icon: { icon: "tabler-chart-line" },
        action: "read",
        subject: "Auth",
      },
      {
        title: "Organization",
        to: { name: "about-us-id", params: { id: 2 } },
        icon: { icon: "tabler-chart-pie" },
        action: "read",
        subject: "Auth",
      }, {
        title: "Administrator",
        to: { name: "administrator" },
        icon: { icon: "tabler-chart-pie" },
        action: "read",
        subject: "Auth",
      },
    ],
    action: "read",
    subject: "Auth",
  },
];
