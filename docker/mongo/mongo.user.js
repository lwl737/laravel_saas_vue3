// create super DBA 'dba_root' and DBA of pxjz 'dbz_pxjz'
db = db.getSiblingDB('admin');
// isRootExist = (!!db.system.users.findOne({user:'root'}));
// create super DBA
db.createUser({
    user: 'root',
    pwd: '0epbavnfpCR750t3Ms4Tw',
    customData: {desc: '超级管理员'},
    roles: ['root']
});
