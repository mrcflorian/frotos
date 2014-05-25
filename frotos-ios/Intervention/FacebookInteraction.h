//
//  FacebookInteraction.h
//  Intervention
//
//  Created by Florian Marcu on 3/21/14.
//  Copyright (c) 2014 Florian Marcu. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <FacebookSDK/FacebookSDK.h>

@interface FacebookInteraction : NSObject

+ (void)getMeWithCompletionBlock:(void (^)(FBRequestConnection*, id, NSError*))completionBlock;
+ (void)getMyProfilePictureWithCompletionBlock:(void (^)(FBRequestConnection*, id, NSError*))completionBlock;
+ (void)getMyFriendsWithCompletionBlock:(void (^)(FBRequestConnection*, id, NSError*))completionBlock;
+ (void)getFriendProfilePictureWithUserId:(NSString *)user_id completionBlock:(void (^)(FBRequestConnection*, id, NSError*))completionBlock;

@end
