project(web2pici)

cmake_minimum_required(VERSION 2.8.7)

find_package(Qt3 REQUIRED) # find and setup Qt3 for this project
find_package(KDE5 REQUIRED) # find and setup KDE3 for this project
#find_library(qtlib QT3)

add_definitions(${QT_DEFINITIONS} ${KDE5_DEFINITIONS})

# tell cmake where to search for libraries:
link_directories(${KDE5_LIB_DIR} ${QT_QT_LIBRARY} qt-mt)

# tell cmake where to search for Qt/KDE headers:
include_directories(${KDE5_INCLUDE_DIR} ${QT_INCLUDE_DIR})

# tell cmake to process CMakeLists.txt in that subdirectory
#add_subdirectory(src)
kde5_automoc(web2pici.cpp)

add_executable(web2pici web2pici.cpp)

target_link_libraries(web2pici khtml ${QT_QT_LIBRARY} ${KDE5_KDECORE_LIBRARY})

install_targets( /bin web2pici)
