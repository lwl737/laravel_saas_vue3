rs.initiate({
  _id: "cmdbrs",
  members: [
    { _id: 0, host: "172.16.1.5:27017", priority: 2 },
    { _id: 1, host: "172.16.1.10:27017" },
  ],
  settings: {
    authorization: true,
    authenticationKeyFile: "/mongo/key/keyFile.key"
  }
});
