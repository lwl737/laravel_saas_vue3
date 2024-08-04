#!/bin/sh
# 设置 MySQL 用户名和密码
MYSQL_USER="root"
MYSQL_PASSWORD="0epbavnfpCR750t3Ms4Tw"

# 要创建的数据库名
DEV_DB_NAME="dev"
# 使用 mysql 命令来连接到 MySQL 并检查数据库是否存在
mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -e "use $DEV_DB_NAME" 2>/dev/null

# 检查上一条命令的退出状态码
if [ $? -eq 0 ]; then
    echo "The database already exists."
else
    # 如果数据库不存在，则创建它
    echo "Creating database..."
    mysql -u $MYSQL_USER -p$MYSQL_PASSWORD << EOF
CREATE DATABASE $DEV_DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EOF

fi

LARAVEL_DB_NAME="laravel"
# 使用 mysql 命令来连接到 MySQL 并检查数据库是否存在
mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -e "use $LARAVEL_DB_NAME" 2>/dev/null

# 检查上一条命令的退出状态码
if [ $? -eq 0 ]; then
    echo "The database already exists."
else
    # 如果数据库不存在，则创建它
    echo "Creating database..."
    mysql -u $MYSQL_USER -p$MYSQL_PASSWORD << EOF
CREATE DATABASE $LARAVEL_DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EOF
fi
