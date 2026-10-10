rs.initiate({
  _id: "cmdbrs",
  members: [
    { _id: 0, host: "lsv_mongo:27017", priority: 2 },
    { _id: 1, host: "lsv_mongo_replica:27017" },
  ],
  settings: {
    authorization: true,
    authenticationKeyFile: "/mongo/key/keyFile.key"
  }
});
